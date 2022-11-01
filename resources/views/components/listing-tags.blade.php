@props(['tagsCsv'])

<ul class="flex">
    @foreach(explode(', ',$tagsCsv) as $csv)
        <li class="flex items-center justify-center bg-black text-white rounded-xl py-1 px-3 mr-2 text-xs">
            <a href="/?tag={{$csv}}">{{$csv}}</a>
        </li>
    @endforeach
</ul>
