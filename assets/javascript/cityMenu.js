const cityInput = document.getElementById('cityInput');
const cityList = document.getElementById('cityList'); 
const submitButton = document.getElementById('submitButton');

cityInput.addEventListener('input', () => {
  const searchValue = cityInput.value.trim();

  if (searchValue.length > 2) {
    fetch(
      `https://api.openweathermap.org/data/2.5/find?q=${searchValue}&appid=f706043c37cf288ca9e408f9ef66a9c8`
    )
      .then((response) => response.json())
      .then((data) => {
        cityList.innerHTML = '';
        const cities = data.list;
        console.log(cities);

        cities.forEach((city) => {
          const cityName = city.name;
          const cityCountry = city.sys.country;
          const cityLon = city.coord.lon;
          const cityLat = city.coord.lat;
          const cityId = city.id;

          const listItem = document.createElement('div');
          listItem.classList.add('city-list-item');
          cityList.appendChild(listItem);

          const cityInfo = document.createElement('strong');
          cityInfo.textContent = cityName + ', ' + cityCountry;

          const coordinates = document.createElement('span');
          coordinates.innerHTML = '<br>(' + cityLon + ', ' + cityLat + ')';
          coordinates.classList.add('small');

          listItem.appendChild(cityInfo);
          listItem.appendChild(coordinates);
          listItem.addEventListener('click', () => {
            cityInput.value = cityId;
            cityList.innerHTML = '';
            submitButton.click();
          });
        });
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  } else {
    cityList.innerHTML = '';
  }
});
