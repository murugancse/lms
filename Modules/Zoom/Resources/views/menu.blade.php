<li>
    <a href="#" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <i class="fas fa-vr-cardboard"></i>
        </div>
        <div class="nav_title">
            <span>{{__('zoom.Zoom')}}</span>
        </div>
    </a>
    <ul>

        @if (permissionCheck('zoom.settings'))
            <li>
                <a href="{{ route('zoom.settings') }}">  {{__('zoom.Zoom Settings')}}</a>
            </li>
        @endif
        @if (auth()->user()->role_id == 2) 
            @if (permissionCheck('virtual-class.index'))
                <li><a href="{{ route('virtual-class.index') }}">  {{__('virtual-class.Class List')}}</a></li>
            @endif

        @endif
    </ul>
</li>
