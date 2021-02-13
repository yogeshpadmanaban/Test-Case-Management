<ul>
@foreach($childs as $child)
	<li>
	    {{ $child->module_name }}
	@if(count($child->childs))
            @include('manageChild',['childs' => $child->childs])
        @endif
	</li>
@endforeach
</ul>