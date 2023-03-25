const ctx = document.getElementById('myChart');
options = {
    scales: {
        y: {
            ticks:{
                color: 'white'
            },
            beginAtZero: false
        },
        x: {
            ticks:{
                color: 'white'
            }
        }
    }
};
var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
        label: 'UF',
        data: [],
        borderWidth: 1
        }]
    },
    options: options
});

$("input[type=date]").change(function(e) {
    var from = $("#fecha_from").val();
    var to = $("#fecha_to").val();

    if(from == null || from == undefined || from == ""){
        alert("Debe Ingresar una fecha desde")
        return
    }
    if(to == null || to == undefined || to == ""){
        //alert("Debe Ingresar una fecha hasta")
        return
    }

    $.ajax({
        url:`/api/chart?from=${from}&to=${to}`,
        type: 'get',
        contentType: 'application/json',
        success:function(response){
            let label = response.map(row => row.fechaIndicador);

            const groups = Object.values(response.reduce((acc, cur) => {
                acc[cur.codigoIndicador] ??= [];
                acc[cur.codigoIndicador].push(cur);
                return acc;
            }, {}));

            let ordenados =  groups.map(group => ({
                codigoIndicador: group[0].codigoIndicador,
                photos: group
            }));

            dataset = [];
            ordenados.forEach(element => {
                let data = element.photos.map(row => {
                    return {x: row.fechaIndicador, y: row.valorIndicador}
                });
                dataset.push({
                    label: element.codigoIndicador,
                    data: data,
                    borderWidth: 1,
                });
            });
            
            chart.destroy();
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: label,
                    datasets: dataset
                },
                options: options
            });

        },
        error:function(error){
            alert(error.responseJSON.errors[0])
        }
    });
    
});