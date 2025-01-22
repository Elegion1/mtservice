<!-- resources/views/components/table.blade.php -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th class="{{ $header['class'] ?? '' }}" style="{{ $header['style'] ?? '' }}">{{ $header['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>