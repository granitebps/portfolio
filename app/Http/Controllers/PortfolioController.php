<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Portfolio;
use App\PortfolioPic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function index()
    {
        $data['title'] = 'Portfolio List';
        $data['portfolio'] = Portfolio::all();
        return view('admin.portfolio.index')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Portfolio';
        return view('admin.portfolio.create')->with($data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'desc' => 'required',
            'type' => 'required',
            'thumbnail' => 'required|image|max:2048',
            'pic' => 'required',
            'pic.*' => 'image|max:512',
        ]);
        if (!empty($request->url)) {
            $this->validate($request, [
                'url' => 'url',
            ]);
        }

        DB::beginTransaction();
        try {
            $thumbnail = $request->thumbnail;
            $folderName = Str::slug($request->name, '-');
            $thumbnailName = 'thumbnail-' . str_replace(' ', '_', $thumbnail->getClientOriginalName());
            // Storage::putFileAs('public/images/portfolio/' . $folderName, $thumbnail, $thumbnailName);

            // Hosting
            $thumbnail->storeAs('portfolio/' . $folderName, $thumbnailName, 'hosting');

            $portfolio = Portfolio::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
                'thumbnail' => $thumbnailName
            ]);

            $pic = $request->pic;
            foreach ($pic as $image) {
                $imageName = str_replace(' ', '_', $image->getClientOriginalName());

                // Hosting
                $image->storeAs('portfolio/' . $folderName, $imageName, 'hosting');

                // Storage::putFileAs('public/images/portfolio/' . $folderName, $image, $imageName);
                PortfolioPic::create([
                    'portfolio_id' => $portfolio->id,
                    'pic' => $imageName
                ]);
            }

            DB::commit();
            Session::flash('success', 'Portfolio Created');
            return redirect()->route('portfolio.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Portfolio';
        $data['portfolio'] = Portfolio::findOrFail($id);
        return view('admin.portfolio.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|max:255',
            'desc' => 'required',
            'type' => 'required',
        ]);
        if (!empty($request->url)) {
            $this->validate($request, [
                'url' => 'url',
            ]);
        }

        DB::beginTransaction();
        try {
            $oldFolderName = Str::slug($portfolio->name, '-');
            $folderName = Str::slug($request->name, '-');
            if ($request->name != $portfolio->name) {
                // Storage::move('public/images/portfolio/' . $oldFolderName, 'public/images/portfolio/' . $folderName);
                // Storage::deleteDirectory('public/images/portfolio/' . $oldFolderName);

                // Hosting
                Storage::disk('hosting')->move('portfolio/' . $oldFolderName, 'portfolio/' . $folderName);
                Storage::disk('hosting')->deleteDirectory('portfolio/' . $oldFolderName);
            }

            if ($request->hasFile('thumbnail')) {
                $this->validate($request, [
                    'thumbnail' => 'required|image|max:2048',
                ]);
                $thumbnail = $request->thumbnail;
                $thumbnailName = 'thumbnail-' . str_replace(' ', '_', $thumbnail->getClientOriginalName());
                // if (File::exists(public_path() . '/storage/images/portfolio/' . $oldFolderName)) {

                // Hosting
                if (File::exists(public_path() . '/images/portfolio/' . $oldFolderName)) {
                    Storage::disk('hosting')->delete('portfolio/' . $oldFolderName . '/' . $portfolio->thumbnail);

                    // Storage::delete('public/images/portfolio/' . $oldFolderName . '/' . $portfolio->thumbnail);
                } else {

                    // Hosting
                    Storage::disk('hosting')->delete('portfolio/' . $folderName . '/' . $portfolio->thumbnail);

                    // Storage::delete('public/images/portfolio/' . $folderName . '/' . $portfolio->thumbnail);
                }

                // Hosting
                $thumbnail->storeAs('portfolio/' . $folderName, $thumbnailName, 'hosting');

                Storage::putFileAs('public/images/portfolio/' . $folderName, $thumbnail, $thumbnailName);
                $portfolio->update([
                    'thumbnail' => $thumbnailName
                ]);
            }

            $portfolio->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'type' => $request->type,
                'url' => $request->url,
            ]);

            if ($request->hasFile('pic')) {
                $this->validate($request, [
                    'pic' => 'required',
                    'pic.*' => 'image|max:2048',
                ]);
                $pic = $request->pic;
                $portfolioPic = PortfolioPic::where('portfolio_id', $portfolio->id)->get();
                foreach ($portfolioPic as $value) {
                    // if (File::exists(public_path() . '/storage/images/portfolio/' . $oldFolderName)) {

                    // // Hosting
                    if (File::exists(public_path() . '/images/portfolio/' . $oldFolderName)) {
                        Storage::disk('hosting')->delete('portfolio/' . $oldFolderName . '/' . $value->pic);

                        // Storage::delete('public/images/portfolio/' . $oldFolderName . '/' . $value->pic);
                    } else {
                        // Storage::delete('public/images/portfolio/' . $folderName . '/' . $value->pic);

                        // Hosting
                        Storage::disk('hosting')->delete('portfolio/' . $folderName . '/' . $value->pic);
                    }
                    $value->delete();
                }
                foreach ($pic as $image) {
                    $imageName = str_replace(' ', '_', $image->getClientOriginalName());
                    // Storage::putFileAs('public/images/portfolio/' . $folderName, $image, $imageName);

                    // Hosting
                    $image->storeAs('portfolio/' . $folderName, $imageName, 'hosting');

                    PortfolioPic::create([
                        'portfolio_id' => $portfolio->id,
                        'pic' => $imageName
                    ]);
                }
            }

            DB::commit();
            Session::flash('success', 'Portfolio Edited');
            return redirect()->route('portfolio.index');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        DB::beginTransaction();
        try {
            $folderName = Str::slug($portfolio->name, '-');
            // Storage::deleteDirectory('public/images/portfolio/' . $folderName);

            // Hosting
            Storage::disk('hosting')->deleteDirectory('portfolio/' . $folderName);

            $portfolio->pic()->delete();
            $portfolio->delete();

            DB::commit();
            Session::flash('success', 'Portfolio Deleted');
            return redirect()->route('portfolio.index');
        } catch (\Exception $e) {
            throw $e;
            DB::rollback();
            Session::flash('error', 'Something Wrong');
            return redirect()->back();
        }
    }

    public function preview(Request $request)
    {
        $portfolio = Portfolio::findOrFail($request->id);
        $folder = Str::slug($portfolio->name, '-');
        // $pathThumbnail = asset('storage/images/portfolio/' . $folder . '/' . $portfolio->thumbnail);

        // Hosting
        $pathThumbnail = asset('images/portfolio/' . $folder . '/' . $portfolio->thumbnail);

        $output = '
            <h5 class="text-center">Thumbnail</h5>
            <a target="_blank" href="' . $pathThumbnail . '"><img class="img-thumbnail" src="' . $pathThumbnail . '" alt=""></a><hr>
            <h5 class="text-center">Picture</h5>
        ';
        foreach ($portfolio->pic as $index => $file) {
            // $path = asset('storage/images/portfolio/' . $folder . '/' . $file->pic);

            // Hosting
            $path = asset('images/portfolio/' . $folder . '/' . $file->pic);

            $output .= '
                <a target="_blank" href="' . $path . '"><img class="img-thumbnail" src="' . $path . '" alt=""></a><br>
            ';
        }
        echo $output;
    }
}
