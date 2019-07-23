<script>
  window.addEventListener('load',function() {
      if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(funcExito, funcError, {});
      } else {
          alert('No geolocation supported');
      }
  },false);

    function funcExito(result) {

      var latitude = document.querySelector('input[name="latitude"]');
      var longitude = document.querySelector('input[name="longitude"]');

      latitude.value = result.coords.latitude;
      longitude.value = result.coords.longitude;
    }

    function funcError(err) {
      alert(err.message);
    }
</script>
