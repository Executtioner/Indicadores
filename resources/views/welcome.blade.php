@extends('layouts.base')
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    
@stop
@section('content')
    <section id="tables">
        <h2>Indicadores</h2>
        <button class="contrast" data-target="modal-save" onClick="toggleModal(event)">Nuevo Indicador</button>
        <figure>
        <table role="grid">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Código</th>
                <th scope="col">Unidad de Medida</th>
                <th scope="col">Valor</th>
                <th scope="col">Fecha</th>
                <th scope="col">Opciones</th>
            </tr>
            </thead>
            <tbody id="table-content">
            </tbody>
        </table>
        </figure>
        <div class="pagination">

        </div>
        </section>

@stop
@section('modal')
<dialog id="modal-save">
        <article>
            <a href="#close" aria-label="Close" class="close" data-target="modal-save" onClick="toggleModal(event)">
            </a>
            <h3>Crear Indicador</h3>
            <form method="POST" action="" id="form-save">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="email" placeholder="Nombre Unidad Medida" required>

                <div class="grid">

                    <label for="codigo">
                    Código
                    <input type="text" id="codigo" name="codigo" placeholder="Código" required>
                    </label>

                    <label for="unidad">
                    Unidad de Medida
                    <input type="text" id="unidad" name="unidad" placeholder="Unidad" required>
                    </label>

                </div>

                <div class="grid">

                    <label for="valor">
                    Valor
                    <input type="number" id="valor" name="valor" placeholder="123" required>
                    </label>

                    <label for="fecha">
                    Fecha
                    <input type="date" id="fecha" name="fecha" required pattern="\d{4}-\d{2}-\d{2}">
                    </label>

                </div>

                <button type="submit" id="save">Guardar</button>

            </form>
        </article>
    </dialog>

    <dialog id="modal-update">
        <article>
            <a href="#close" aria-label="Close" class="close" data-target="modal-update" onClick="toggleModal(event)">
            </a>
            <h3>Crear Indicador</h3>
            <form method="POST" action="" id="form-update">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="email" placeholder="Nombre Unidad Medida" required>

                <div class="grid">

                    <label for="codigo">
                    Código
                    <input type="text" id="codigo" name="codigo" placeholder="Código" required>
                    </label>

                    <label for="unidad">
                    Unidad de Medida
                    <input type="text" id="unidad" name="unidad" placeholder="Unidad" required>
                    </label>

                </div>

                <div class="grid">

                    <label for="valor">
                    Valor
                    <input type="number" step="any" id="valor" name="valor" placeholder="123" required>
                    </label>

                    <label for="fecha">
                    Fecha
                    <input type="date" id="fecha" name="fecha" required pattern="\d{4}-\d{2}-\d{2}">
                    </label>

                </div>

                <button type="submit" id="update">Guardar</button>

            </form>
        </article>
    </dialog>
@stop
@section('scripts')
    <script src="{{ asset('js/script.js')}}"></script>
@stop
