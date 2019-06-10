<script>

    //llama a la función  de geolocalización cuando carga la página
    window.addEventListener('load',function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(insertPosition, funcError, {});
        } else {
            alert('No geolocation supported');
        }
    },false);


    //inserta en el formulario
    function insertPosition(position) {
        $("input[name='latitude']").val(position.coords.latitude);
        $("input[name='longitude']").val(position.coords.longitude);
    }

    function funcError(err) {
      alert(err.message);
    }
</script>
