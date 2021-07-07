<ul class="list-unstyled level-1">
    @foreach( $tree as $ancestor )
    <li>
        <a href="{{ url('/categoria/' . $ancestor[0]->slug) }}" class="h2">{{ $ancestor[0]->name }}</a>
        @isset( $ancestor[1] )
            <ul class="list-unstyled level-2">
                <li>
                    <a href="{{ url('/categoria/' . $ancestor[1]->slug) }}" class="h3">
                        {{ $ancestor[1]->name }}
                    </a>
                    @isset( $ancestor[2] )
                        <ul class="list-unstyled level-3">
                            <li>
                                <a href="{{ url('/categoria/' . $ancestor[2]->slug) }}" class="">
                                    {{ $ancestor[2]->name }}
                                </a>
                            </li>
                        </ul>
                    @endisset
                </li>
            </ul>
        @endisset
    </li>
    @endforeach
    <li>
</ul>
