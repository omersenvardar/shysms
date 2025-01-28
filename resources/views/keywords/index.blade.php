<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anahtar Kelimeler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Anahtar Kelimeler</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Anahtar Kelime</th>
        </tr>
        </thead>
        <tbody>
        @forelse($keywords as $keyword)
            <tr>
                <td>{{ $keyword['id'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">Hiç anahtar kelime bulunamadı.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
