@extends('layouts.base')
@section('content')
    <section id="tables">
        <h2>Gráfico Indicadores</h2>
        <canvas id="myChart"></canvas>
            <div class="grid">

                <label for="fecha desde">
                Fecha Desde
                <input type="date" id="fecha_from" name="fecha_from" required pattern="\d{4}-\d{2}-\d{2}" required>
                </label>

                <label for="fecha hasta">
                Fecha Hasta
                <input type="date" id="fecha_to" name="fecha_to" required pattern="\d{4}-\d{2}-\d{2}" required>
                </label>

            </div>
    </section>
@stop
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart-indicadores.js')}}"></script>
@stop
