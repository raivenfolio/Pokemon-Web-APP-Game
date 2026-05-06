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
  <input type="text" id="letter" maxlength="1" placeholder="Guess a letter">
  <button onclick="guessLetter()">Guess</button>
  <p id="result"></p>
  <h2>Captured Pokémon</h2>
  <div id="collection"></div>

  <script>
  let pokemons = [];
  let current, displayWord, health;

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
    document.getElementById("pokemon").src = current.img;
    document.getElementById("pokemon").className = "silhouette";
    document.getElementById("word").textContent = displayWord.join(" ");
    document.getElementById("health").textContent = "❤️".repeat(health);
    document.getElementById("result").textContent = "";
  }

  function guessLetter() {
    const letter = document.getElementById("letter").value.toLowerCase();
    let correct = false;
    for (let i = 0; i < current.name.length; i++) {
      if (current.name[i].toLowerCase() === letter) {
        displayWord[i] = current.name[i];
        correct = true;
      }
    }
    if (!correct) {
      health--;
    }
    document.getElementById("word").textContent = displayWord.join(" ");
    document.getElementById("health").textContent = "❤️".repeat(health);
    document.getElementById("letter").value = "";

    if (displayWord.join("") === current.name) {
      document.getElementById("result").textContent = "You captured " + current.name + "!";
      document.getElementById("pokemon").className = "";
      document.getElementById("collection").innerHTML += `<img src="${current.img}">`;
      setTimeout(newGame, 2000);
    } else if (health <= 0) {
      document.getElementById("result").textContent = "You fainted! It was " + current.name + ".";
      document.getElementById("pokemon").className = "";
      setTimeout(newGame, 2000);
    }
  }
</script>

</body>
</html>
