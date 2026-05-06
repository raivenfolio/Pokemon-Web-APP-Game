<!DOCTYPE html>
<html>
<head>
  <title>Pokémon Capture Game</title>
  <style>
    body { font-family: Arial; text-align: center; }
    img.silhouette { filter: brightness(0); width: 200px; }
    #health { font-size: 20px; color: red; }
    #collection img { width: 50px; margin: 5px; }
  </style>
</head>
<body>
  <h1>Guess That Pokemon</h1>
  <img id="pokemon" class="silhouette" src="" alt="Pokemon silhouette">
  <p id="word"></p>
  <p id="health"></p>
  <input type="text" id="letter" maxlength="1" placeholder="Guess a letter" onkeypress="if(event.key === 'Enter') guessLetter()">
  <button onclick="guessLetter()">Guess</button>
  <p id="result"></p>

  <p>Wrong Guesses: <span id="wrong-letters" style="font-weight: bold; color: #555;">None</span></p>

  <h2>Captured Pokémon</h2>
  <div id="collection"></div>

  <script>
  let pokemons = [];
  let current, displayWord, health;

  //changes here below
  let isPaused = false;

  //changes
  let wrongLetters = [];

  // Load JSON file
  fetch("gen1.json")
    .then(response => response.json())
    .then(data => {
      pokemons = data;
      newGame(); 
    });

  function newGame() {
    current = pokemons[Math.floor(Math.random() * pokemons.length)];
    displayWord = "_".repeat(current.name.length).split("");
    health = 5;

    //changes here below
    isPaused = false;

    //changes
    wrongLetters = [];
    document.getElementById("wrong-letters").textContent = "None";
    
    document.getElementById("pokemon").src = current.img;
    document.getElementById("pokemon").className = "silhouette";
    document.getElementById("word").textContent = displayWord.join(" ");
    document.getElementById("health").textContent = "❤️".repeat(health);
    document.getElementById("result").textContent = "";
    //changes here below
    document.getElementById("letter").disabled = false;
  }

  function guessLetter() {
    if (isPaused) return; //changes
    const letter = document.getElementById("letter").value.toLowerCase();
    if (!letter) return; //changes

    if (/\d/.test(letter)) {
      document.getElementById("letter").value = "";
      return;
    }


    let correct = false;
    for (let i = 0; i < current.name.length; i++) {
      if (current.name[i].toLowerCase() === letter) {
        displayWord[i] = current.name[i];
        correct = true;
      }
    }
    if (!correct) {
      health--;
      
      // Track wrong letters here
      if (!wrongLetters.includes(letter)) {
        wrongLetters.push(letter);
      }
      document.getElementById("wrong-letters").textContent = wrongLetters.join(", ");



      isPaused = true;
      document.getElementById("result").textContent = "Incorrect guess! It was not " + letter + ".";
      document.getElementById("letter").disabled = true;
      
      setTimeout(() => {
        isPaused = false;
        document.getElementById("letter").disabled = false;
        document.getElementById("result").textContent = "";
      }, 2000); // 2 seconds pause




    }
    document.getElementById("word").textContent = displayWord.join(" ");
    document.getElementById("health").textContent = "❤️".repeat(health);
    document.getElementById("letter").value = "";

    if (displayWord.join("") === current.name) {
      isPaused = true;
      document.getElementById("result").textContent = "You captured " + current.name + "!";
      document.getElementById("pokemon").className = "";
      document.getElementById("collection").innerHTML += `<img src="${current.img}">`;
      setTimeout(newGame, 1000);
    } else if (health <= 0) {
      isPaused = true;
      document.getElementById("result").textContent = "You fainted! It was " + current.name + ".";
      document.getElementById("pokemon").className = "";
      setTimeout(newGame, 1000);
    }
  }
</script>

</body>
</html>
