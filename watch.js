const numberHours = document.querySelector('.number-hours');
const barSeconds = document.querySelector('.bar-seconds');

const numberElement = [];
const barElement = [];

//create number hours
for (let i = 1; i <= 12; i++)
{
    numberElement.push
    (
        `<span style="--index:${i};"><p>${i}</p></span>`
    );
}

numberHours.insertAdjacentHTML("afterbegin", numberElement.join(""));

// create bar seconds
for (let i = 1; i <= 60; i++)
{
    barElement.push
    (
        `<span style="--index:${i};"><p></p></span>`
    );
}

barSeconds.insertAdjacentHTML("afterbegin", barElement.join(""));

const handHours = document.querySelector('.hand.hours');
const handMinutes = document.querySelector('.hand.minutes');
const handSeconds = document.querySelector('.hand.seconds');

function getCurrentTime()
    {
        let date = new Date();
        let getcurrentHours = date.getHours();
        let getcurrentMinutes = date.getMinutes();
        let getcurrentSeconds = date.getSeconds();

    /*--------------------------------------------------------------------------
	60 seconds = 360deg so 1 sec = 360 / 60 = deg
	60 minutes = 360deg so 1 min = 360 / 60 = deg
	12 hours = 360deg so 1 hrs = 360 / 12 = 30deg

	1 hrs = 30deg that means 60 min = 30deg so 1 min = 30/60 = 0.5 or 1/2 deg

	so formula for hours is (hours * 30 + minutes / 2)
	---------------------------------------------------------------------------*/


        handHours.style.transform = `rotate(${getcurrentHours * 30 + getcurrentMinutes / 2}deg)`;
        handMinutes.style.transform = `rotate(${getcurrentMinutes * 6}deg)`;
        handSeconds.style.transform = `rotate(${getcurrentSeconds * 6}deg)`;
    }

//call getCurrentTime function on page load
getCurrentTime();

//call getCurrentTime to set clock hands every second 
setInterval(getCurrentTime, 1000); //1000ms = 1s