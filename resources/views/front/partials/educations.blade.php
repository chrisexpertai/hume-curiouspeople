@if($education)
    <ul>
        <li>
            <strong>{{ tr('School Name:') }}</strong> {{ $education->school_name }}<br>
            <strong>{{ tr('Degree:') }}</strong> {{ $education->degree }}
        </li>
    </ul>
@else
    <p>{{ tr('No education entries found.') }}</p>
@endif
