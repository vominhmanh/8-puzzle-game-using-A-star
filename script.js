

      var nextStartState = [];
      var nextAnswer = [];
      var nextAnswerPathLength = 0;
      var nextNinePosition;

      var startState = [];
      var currentState = [];
      var answer = false;
      var answerPathLength = 0;
      var moveCount = 0;
      var newgameClick = false;
      var finishInit = false;
      var ninePosition;

      
      $("document").ready(function () {
        createNewGame();
      });

      function newGame() {
        newgameClick = true;
        $('#next').attr('disabled',true);
        $('#back').attr('disabled',true);
        $('#solutionMoveCount').text('')
        $('#solutionButton').attr('disabled',false);
        if (!finishInit) {
          //JSON.stringify(startState) != JSON.stringify(nextStartState)
          $("#exampleModalCenter").modal("show");
        } else {
          if (finishInit) {
            getNewGame();
            
            newgameClick = false;
            createNewGame(); // create next NewGame
          }
        }

        moveCount = 0;
        $("#moveCount").text(moveCount);
      }

      function replay() {
        moveCount = 0;
        ninePosition = findNinePosition(startState);
        $("#moveCount").text(moveCount);
        currentState = Array.from(startState);
        updateState(startState);
      }

      // Tạo một mảng ngẫu nhiên vd:[0,3,7,6,2,5,8,9,1,4]
      function shuffleMatrix() {
        var arr = [0];
        while (arr.length <= 9) {
          var r = Math.floor(Math.random() * 9) + 1;
          if (arr.indexOf(r) === -1) arr.push(r);
        }
        return arr;
      }

      function updateState(state) {
        currentState = Array.from(state);
        ninePosition = findNinePosition(currentState);
        state.forEach(function (eachElement, index) {
          if (eachElement == 9) {
            $(`#btn${index}`).addClass('blankButton');
            $(`#btn${index}`).text('');
          } else {
            $(`#btn${index}`).text(eachElement);
            $(`#btn${index}`).removeClass('blankButton');
          }
        });
        setHover();
      }

      function move(id) {
        var swap = false;
        
        if (ninePosition != id) {
          if (id - 3 == ninePosition) {
            swap = true;
          } else if (id + 3 == ninePosition) {
            swap = true;
          } else if (id - 1 == ninePosition) {
            if (id % 3 != 1) {
              swap = true;
            }
          } else if (id + 1 == ninePosition) {
            if (id % 3 != 0) {
              swap = true;
            }
          }
          console.log(ninePosition);
          if (swap) {
            $("#moveCount").text(++moveCount);
            var tmp = currentState[id];
            currentState[id] = currentState[ninePosition];
            currentState[ninePosition] = tmp;
            ninePosition = id;
            updateState(currentState);
            checkWin();
          }
        }
      }

      function checkWin() {
        if (
          JSON.stringify(currentState) ==
          JSON.stringify([0, 1, 2, 3, 4, 5, 6, 7, 8, 9])
        ) {
          $("#winnerModal").modal("show");
          currentState = [];
        }
      }

      function getNewGame() {
        startState = Array.from(nextStartState);
        ninePosition = nextNinePosition;
        updateState(startState);
        currentState = Array.from(startState);
        answer = Array.from(nextAnswer);
        answerPathLength = nextAnswerPathLength;
      }

      function solutionForStartState () {
         $('.moving').attr('disabled',false);
         $('.moving').attr('disabled',false);
         updateState(startState);
         i = 0;
          $('#startMove').attr('style','display: true');
          $('#currentMove').attr('style','display: none');

      }

      function solutionForCurrentState () {
         $('.moving').attr('disabled',false);
         $('.moving').attr('disabled',false);
         getSolution();
        i = 0;
        $('#startMove').attr('style','display: none');
          $('#currentMove').attr('style','display: true');
      }

      function findNinePosition(state) {
        for (var i=1; i<=9; i++) {
          if (state[i] == 9) {
            return i;
          }
        }
      }

      function setHover () {
        for (var i=1; i<= 9; i++) {
          $(`#btn${i}`).removeClass('isHovered');
        }

        $(`#btn${ninePosition-3}`).addClass('isHovered');
        $(`#btn${ninePosition+3}`).addClass('isHovered');
        if ( (ninePosition - 1) % 3 != 0) $(`#btn${ninePosition-1}`).addClass('isHovered');
        if ( (ninePosition + 1) % 3 != 1) $(`#btn${ninePosition+1}`).addClass('isHovered');
      }

      function createNewGame() {
        var firstState = shuffleMatrix();
        finishInit = false;
       
        $.ajax({
          type: "get",
          url: "astar_solver.php",
          data: { startState: JSON.stringify(Array.from(firstState)) },

          success: function (response) {
            $("#message").text(response);
            try {
              nextAnswer = JSON.parse(response);
            } catch {
              nextAnswer = false;  // trả về false 
                                    // nếu không tìm thấy tập tin phản hồi
            }
            if (nextAnswer) {
              nextAnswer = nextAnswer.map(function (currentObj) {
                var mapArray = Object.values(currentObj);
                mapArray.unshift(0);
                return mapArray;
              });

              nextAnswer = nextAnswer.reverse();
              nextAnswer.unshift(firstState)
              nextAnswerPathLength = nextAnswer.length-1;
              nextStartState = Array.from(nextAnswer[0]);
              nextNinePosition = findNinePosition(nextStartState);
              console.log(nextAnswer);
              finishInit = true;

              if (newgameClick) {
                getNewGame();
                newgameClick = false;
                createNewGame(); // create next NewGame
              }

              setTimeout(() => {
                $("#exampleModalCenter").modal("hide");
              }, 500);
              finishInit = true;
            } else {
              createNewGame();
            }
          },
        });
      }

      var finishSolving;
      var Answer;
      var AnswerPathLength;
      var StartState;
      var NinePosition;
      
      function getSolution() {
        var firstState = currentState;
        finishSolving = false;
        $("#exampleModalCenterTitle").text("Đang tìm lời giải...");
        $("#exampleModalCenter").modal("show");
        $('#exampleModalCenter .modal-footer').attr('style','display: none');

        setTimeout ( () => {
          $('#exampleModalCenter .modal-footer').attr('style',true);
        }, 2000);

        $.ajax({
          type: "get",
          url: "astar_solver.php",
          data: { startState: JSON.stringify(Array.from(firstState)) },

          success: function (response) {
            try {
              Answer = JSON.parse(response);
            } catch {
              Answer = false;  // trả về false 
                                    // nếu không tìm thấy tập tin phản hồi
            }
            if (Answer) {
              Answer = Answer.map(function (currentObj) {
                var mapArray = Object.values(currentObj);
                mapArray.unshift(0);
                return mapArray;
              });

              Answer = Answer.reverse();
              Answer.unshift(firstState)
              AnswerPathLength = Answer.length-1;
              StartState = Array.from(Answer[0]);
              NinePosition = findNinePosition(StartState);
              console.log(Answer);
              finishSolving = true;

              setTimeout(() => {
                $("#exampleModalCenter").modal("hide");
              }, 500);
              finishInit = true;
            } else {
              $('#exampleModalCenter .modal-body').html("<span>Rất tiếc, không thể tìm thấy lời giải.</span>");
            }
          },
        });
      }
      
      var i = 0;
  $('#back').click (function () {
    if (i > answerPathLength - 1) {
      i = answerPathLength - 1;
    } 
    if (i < 0) {
      i = 0;
    }
    $('#solutionMoveCount').html(`${i}/${answerPathLength}`)
    updateState(answer[i--]);
  })
  $('#next').click (function () {
    if (i > answerPathLength -1) {
      i = answerPathLength - 1;
    } 
    if (i < 0) {
      i = 0;
    }
    updateState(answer[++i]);
    $('#solutionMoveCount').html(`${i}/${answerPathLength}`)

  })

// back Current
  $('#backCurrent').click (function () {
    if (i > AnswerPathLength - 1) {
      i = AnswerPathLength - 1;
    } 
    if (i < 0) {
      i = 0;
    }
    $('#solutionMoveCount').html(`${i}/${AnswerPathLength}`)
    updateState(Answer[i--]);
  })
  $('#nextCurrent').click (function () {
    if (i > AnswerPathLength -1) {
      i = AnswerPathLength - 1;
    } 
    if (i < 0) {
      i = 0;
    }
    updateState(Answer[++i]);
    $('#solutionMoveCount').html(`${i}/${AnswerPathLength}`)

  })
