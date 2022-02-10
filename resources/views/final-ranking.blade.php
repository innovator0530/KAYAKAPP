@extends('layouts.pdf')

@section('content')
  <div class="table-responsive mb-0">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th colspan="3">
            Open Masculino Largo
          </th>
        </tr>
        <tr>
          <th colspan="3">
            RANKING 2021
          </th>
        </tr>
        <tr class="thead-light">
          <th>Posicion</th>
          <th>Participante</th>
          <th>Suma 3 Mejores</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($data as $datum)
        <tr>
          <td>{{ $datum["posicion"] }}</td>
          <td>{{ $datum["participante"] }}</td>
          <td>{{ $datum["suma_3_mejores"] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection

