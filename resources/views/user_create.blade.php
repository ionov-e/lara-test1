<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register Page</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <h1 class="text-center">Register form</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="" method="post">
        @csrf
        <div class="mb-3">
            <label for="nameInput" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="nameInput" name="nameInput" aria-describedby="nameInput" required>
            <div id="nameInput" class="form-text">We'll never share your api name with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="emailInput" class="form-label">Email address</label>
            <input type="email" class="form-control" id="emailInput" name="emailInput" aria-describedby="emailHelp" required>
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="passwordInput" class="form-label">Password</label>
            <input type="password" class="form-control" id="passwordInput" name="passwordInput" required>
        </div>

        <div class="mb-3">
            <label for="urlInput" class="form-label">Your domain</label>
            <input type="url" class="form-control" id="urlInput" name="urlInput" aria-describedby="urlInput" required>
            <div id="urlInput" class="form-text">We'll never share your domain with anyone else.</div>
        </div>

        <div class="mb-3">
            <label for="keyInput" class="form-label">Your api key</label>
            <input type="text" class="form-control" id="keyInput" name="keyInput" aria-describedby="keyInput" required>
            <div id="keyInput" class="form-text">We'll never share your api key with anyone else.</div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8"
        crossorigin="anonymous"></script>
</body>
</html>
