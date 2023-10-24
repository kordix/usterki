<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .point {
            position: absolute;
            transform: translate(-50%, -50%);
            width: 30px;

            height: 30px;
            border: 1px black solid;
            border-radius: 100%;
            background: blue;
        }
    </style>
</head>

<body>

    <div id="app">

        <div style="position:relative;display:inline-block;">
            <div class="point" v-for="elem in points" :style="{left: `${elem.x * 100}%`,top: `${elem.y * 100}%`}"></div>
            <img src="plan-pietra-matterport-768x542.jpg.webp" style="cursor:pointer"id="image" alt="">
        </div>


    </div>


    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>


    <script>
        Vue.createApp({
            el: '#app',
            data(){
                return {
                      points: [
                        { x: 0.25, y: 0.25 },

                    ],

                }
            },
          
            mounted() {
                let self = this;
                const image = document.getElementById('image');
                const point = document.querySelector('.point');

                image.addEventListener('click', (event) => {
                    const imgWidth = image.offsetWidth;
                    const imgHeight = image.offsetHeight;

                    const offsetX = event.offsetX / imgWidth;
                    const offsetY = event.offsetY / imgHeight;

                    console.log('Kliknięto na pozycji:');
                    console.log('Względem szerokości obrazka: ' + Math.round(offsetX * 1000) / 1000);
                    console.log('Względem wysokości obrazka: ' + Math.round(offsetY * 1000) / 1000);
                    self.points.push({x:offsetX,y:offsetY})
                });
            }
        }).mount('#app')



    </script>
</body>

</html>