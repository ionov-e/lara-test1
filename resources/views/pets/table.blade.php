<div class="d-flex gap-3">
    <a class="btn btn-primary" href="{{ route('pets.show', $pet['id']) }}">View</a>
    <a class="btn btn-success" href="{{ route('pets.edit', $pet['id']) }}">Edit</a>
    <form action="{{ route('pets.destroy', $pet['id'])}}" method="POST">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>
<table class="table table-striped">
    <thead>
    <tr style="text-align: left;">
        <th scope="col">key</th>
        <th scope="col">value</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($pet as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
