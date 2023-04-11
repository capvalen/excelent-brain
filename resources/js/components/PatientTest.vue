<template>
    <main>
        <div class="header">
                <h1 class="h3">Buen día, rellene el siguiente cuestionario, porfavor.</h1>
                <a href="#" class="h3"><i class="fas fa-times"></i></a>
        </div>
        <div class="body">
            <div class="number">
                <div class="active">
                    #01
                </div>
                <div class="total">
                    de 07
                </div>
            </div>
            <div class="bar">
                <div class="completed"></div>
            </div>
            <div class="carta">
                <div class="carta-question">
                    <p>Who you are?</p>
                </div>
                <div class="carta-answers">
                    <div class="carta-answer">
                        <div class="answer-option">A</div>
                        <div class="answer-text">I'm Iron Man</div>            
                    </div>
                    <div class="carta-answer">
                        <div class="answer-option">B</div>
                        <div class="answer-text">I'm your father</div>            
                    </div>
                    <div class="carta-answer">
                        <div class="answer-option">C</div>
                        <div class="answer-text">I'm the walrus</div>            
                    </div>
                    <div class="carta-answer">
                        <div class="answer-option">D</div>
                        <div class="answer-text">I am</div>            
                    </div>
                </div>
                <div class="carta-buttons">
                    <div class="btn-option">A</div>
                    <div class="btn-option">B</div>
                    <div class="btn-option">C</div>
                    <div class="btn-option">D</div>
                </div>
            </div>
        </div>  
    </main>
</template>
<script>
export default {
    
}
</script>
<style>
    .bar{
        height: 10px;
        width: 50%;
        margin-bottom: 10px;
        border-radius: 10px;
        background-color: #fdfdfd;
    }
    .bar .completed{
        height: 10px;
        width: 20%;
        margin-bottom: 10px;
        border-radius: 10px;
        background: rgb(2,0,36);
        background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(88,204,220,1) 0%, rgba(0,176,255,1) 100%);
    }
    .number{
        display:flex;
        font-size: 3em;
        width: 50%;
        align-items: center;
    }
    .number .active{
        font-weight: bold;
        color:#475a63;
    }
    .number .total{
        font-size: 0.5em;
    }
    .header{
        width: 100%;
        height: 10vh;
        display: flex;
        justify-content: space-around;
        align-items: center;
        color: white;
        background: rgb(2,0,36);
        background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(88,204,220,1) 0%, rgba(0,176,255,1) 100%);
    }
    .header h1{
        font-weight: 600;
    }
    .header a{
        color: white;
        text-decoration: none;
    }
    .template{
        width: 80%;
        background-color: red;

    }
    .body{
        height: 90vh;
        display: flex;
        align-items: center;
        flex-direction: column;
        background-color: #edeff1;
    }
    .carta{
        background-color:rgb(255, 255, 255);
        height: auto;
        width: 50%;
        border-radius: 15px;

        display: flex;
        align-items: center;
        flex-direction: column;
    }
    .carta-question{
        color: #0dadf2;
        font-size: 3em;
        font-weight: 500;
        text-align: center;
        background-color: #f8fafb;
        width: 100%;
        border-radius: 15px 15px 0px 0px;
    }
    .carta-answers{
        display: flex;
        width: 50%;
        justify-content: center;
        flex-direction: column;
        color: #455a63;
    }
    .carta-answer{
        display: flex;
        justify-content: flex-start;
        font-size: 1.5em;
        align-items: center;
    }
    .answer-option{
        font-weight: 400;
        font-size: 2em;     
    }
    .answer-text{
        margin-left: 1em;
        font-size: 1em;
    }
    .carta-buttons{
        width: 70%;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .btn-option{
        height: 4em;
        width: 4em;
        border-radius: 35px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background-color:rgb(231, 243, 246);
    }
    .btn-option:hover{
        border: solid 3px #55c8da;
        cursor: pointer;
        color: #455a63;
    }

    @media only screen and (max-width: 600px) {
        .header h1{
            font-size:0.85em;
        }
        .header a{
            font-size:17px;
        }
        .number{
            font-size: 2em;
            width: 75%; 
        }
        .bar{
            width: 75%;
        }
        .carta{
            width: 80%;
        }
        .carta-question{
            font-size:2em;
        }
        .carta-answers{
            width: 80%;
        }
        .carta-answer{
            margin-left: 2em;
        }
        .answer-option{
            font-size: 1em;
        }
        .answer-text{
            font-size: 0.8em;
        }
        .btn-option{
            height: 2.5em;
            width: 2.5em;
        }
    }
</style>