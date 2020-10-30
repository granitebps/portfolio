<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
            <img src="https://s3.ap-southeast-1.amazonaws.com/portfolio-granitebps.com/galeries/1600601933_gbps.png"
                class="logo" alt="Laravel Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>