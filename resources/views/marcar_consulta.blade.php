<!DOCTYPE html>
<html>

<head>
    <title>Marcar Consulta</title>
</head>

<body>
    <h1>Marcar Consulta</h1>
    <form action="{{ route('consultas.store') }}" method="POST">
        @csrf
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br>

        <label for="numero">Número (com código do país, por exemplo, +5511999999999):</label>
        <input type="text" id="numero" name="numero" required>
        <br>

        <label for="inicio_data">Início:</label>
        <input type="datetime-local" id="inicio_data" name="inicio_data" required>
        <br>

        <label for="fim_data">Fim:</label>
        <input type="datetime-local" id="fim_data" name="fim_data" required>
        <br>

        <button type="submit">Marcar Consulta</button>
    </form>
</body>

</html>