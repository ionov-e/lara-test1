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
    <tr>
        <td><br><br></td>
    </tr>
    <tr>
        <td><a href="{{ route('pet.show', $pet['id']) }}">View</a></td>
    </tr>
    <tr>
        <td><a href="{{ route('pet.edit', $pet['id']) }}">Edit</a></td>
    </tr>
    <tr>
        <td>
            <form action="{{ route('pet.destroy' , $pet['id'])}}" method="POST">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="DELETE">
                <button type="submit" class="btn btn-primary">Delete</button>
            </form>
        </td>
    </tr>
    </tbody>
</table>
