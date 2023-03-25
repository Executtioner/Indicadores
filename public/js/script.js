$(document).ready(function(){
    getData()
});

function getData(page = 1){
    $.ajax({
        url:"/api/indicador?page="+page,
        success:function(data){
            $('#table-content').empty();
            data.data.forEach(element => {
                $("#table-content").append(`
                    <tr>
                        <th scope="row">${element.id}</th>
                        <td>${element.nombreIndicador}</td>
                        <td>${element.codigoIndicador}</td>
                        <td>${element.unidadMedidaIndicador}</td>
                        <td>${element.valorIndicador}</td>
                        <td>${element.fechaIndicador}</td>
                        <td class="actions"><a onclick="editar(${element.id})">Editar</a> <a onclick="eliminar(${element.id})">Eliminar</a></td>
                    </tr>
                `);
            });
            $('.pagination').empty();
            data.links.forEach(element => {
                $(".pagination").append(`<a class="page ${element.active? 'active': ''}" data-url="${element.url}">${element.label}</a>`);
            });
        }
    });
}

function editar(id){
    $.ajax({
        url:`/api/indicador/${id}`,
        success:function(data){
            console.log(data.id)
            $("#modal-update #nombre").val(data.nombreIndicador);
            $("#modal-update #codigo").val(data.codigoIndicador);
            $("#modal-update #unidad").val(data.unidadMedidaIndicador);
            $("#modal-update #valor").val(data.valorIndicador);
            $("#modal-update #fecha").val(data.fechaIndicador);
            $("#modal-update #update").attr("data-id", id);
            const modal = document.getElementById("modal-update");
            openModal(modal)
        }
    });
}

function eliminar(id){
    $.ajax({
        url: `/api/indicador/${id}`,
        dataType: 'json',
        type: 'delete',
        contentType: 'application/json',
        success: function( data){
            pageReload();
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
}

function pageReload(){
    let page = $("a.active").data('url')?.split('page=')[1];
    if(isNaN(page)) return
    getData(page);
}

$("#save").click(function(e) {
    e.preventDefault();
    var nombre = $("#modal-save #nombre").val();
    var codigo = $("#modal-save #codigo").val();
    var unidad = $("#modal-save #unidad").val();
    var valor = $("#modal-save #valor").val();
    var fecha = $("#modal-save #fecha").val();
    $.ajax({
        url: '/api/indicador',
        dataType: 'json',
        type: 'post',
        contentType: 'application/json',
        data: JSON.stringify( { "nombreIndicador": nombre, "codigoIndicador": codigo, "unidadMedidaIndicador": unidad, "valorIndicador": valor, "fechaIndicador": fecha } ),
        processData: false,
        success: function( data){
            $('#form-save').trigger("reset");
            pageReload();
            const modal = document.getElementById("modal-save");
            closeModal(modal)
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
});

$("#update").click(function(e) {
    e.preventDefault();
    var nombre = $("#modal-update #nombre").val();
    var codigo = $("#modal-update #codigo").val();
    var unidad = $("#modal-update #unidad").val();
    var valor = $("#modal-update #valor").val();
    var fecha = $("#modal-update #fecha").val();
    var id = $("#modal-update #update").attr('data-id');
    console.log(id)
    $.ajax({
        url: `/api/indicador/${id}`,
        dataType: 'json',
        type: 'put',
        contentType: 'application/json',
        data: JSON.stringify( { "nombreIndicador": nombre, "codigoIndicador": codigo, "unidadMedidaIndicador": unidad, "valorIndicador": valor, "fechaIndicador": fecha } ),
        processData: false,
        success: function( data){
            $('#form-update').trigger("reset");
            pageReload();
            const modal = document.getElementById("modal-update");
            closeModal(modal)
        },
        error: function( jqXhr, textStatus, errorThrown ){
            console.log( errorThrown );
        }
    });
});

$("form").submit(function(e){
    e.preventDefault();
});

$(document).on('click', "a.page", function() {
    let page = $(this).data('url')?.split('page=')[1];
    if(isNaN(page)) return
    getData(page);
});

/*
 * Modal
 *
 * Pico.css - https://picocss.com
 * Copyright 2019-2023 - Licensed under MIT
 */

// Config
const isOpenClass = 'modal-is-open';
const openingClass = 'modal-is-opening';
const closingClass = 'modal-is-closing';
const animationDuration = 400; // ms
let visibleModal = null;


// Toggle modal
const toggleModal = event => {
  event.preventDefault();
  const modal = document.getElementById(event.currentTarget.getAttribute('data-target'));
  (typeof(modal) != 'undefined' && modal != null)
    && isModalOpen(modal) ? closeModal(modal) : openModal(modal)
}

// Is modal open
const isModalOpen = modal => {
  return modal.hasAttribute('open') && modal.getAttribute('open') != 'false' ? true : false;
}

// Open modal
const openModal = modal => {
  if (isScrollbarVisible()) {
    document.documentElement.style.setProperty('--scrollbar-width', `${getScrollbarWidth()}px`);
  }
  document.documentElement.classList.add(isOpenClass, openingClass);
  setTimeout(() => {
    visibleModal = modal;
    document.documentElement.classList.remove(openingClass);
  }, animationDuration);
  modal.setAttribute('open', true);
}

// Close modal
const closeModal = modal => {
  visibleModal = null;
  document.documentElement.classList.add(closingClass);
  setTimeout(() => {
    document.documentElement.classList.remove(closingClass, isOpenClass);
    document.documentElement.style.removeProperty('--scrollbar-width');
    modal.removeAttribute('open');
  }, animationDuration);
}

// Close with a click outside
document.addEventListener('click', event => {
  if (visibleModal != null) {
    const modalContent = visibleModal.querySelector('article');
    const isClickInside = modalContent.contains(event.target);
    !isClickInside && closeModal(visibleModal);
  }
});

// Close with Esc key
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && visibleModal != null) {
    closeModal(visibleModal);
  }
});

// Get scrollbar width
const getScrollbarWidth = () => {

  // Creating invisible container
  const outer = document.createElement('div');
  outer.style.visibility = 'hidden';
  outer.style.overflow = 'scroll'; // forcing scrollbar to appear
  outer.style.msOverflowStyle = 'scrollbar'; // needed for WinJS apps
  document.body.appendChild(outer);

  // Creating inner element and placing it in the container
  const inner = document.createElement('div');
  outer.appendChild(inner);

  // Calculating difference between container's full width and the child width
  const scrollbarWidth = (outer.offsetWidth - inner.offsetWidth);

  // Removing temporary elements from the DOM
  outer.parentNode.removeChild(outer);

  return scrollbarWidth;
}

// Is scrollbar visible
const isScrollbarVisible = () => {
  return document.body.scrollHeight > screen.height;
}