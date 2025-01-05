<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'GBPS')
            <img src="https://is3.cloudhost.id/gbps/misc/gbps.png"
                class="logo" alt="GBPS Logo">
            @else
            {{ $slot }}
            @endif
        </a>
    </td>
</tr>