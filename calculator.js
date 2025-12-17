$(start);

// --- Added global variables ---
let savedValue = null;
let savedOperation = null;

function start() {
    alert("Calculator is ready!");
    let valueinput = $("<div></div>");
    let partial = $("<div id='partial'></div>");   // <--- Added partial computation div

    let one = $("<button>1</button>");
    let two = $("<button>2</button>");
    let three = $("<button>3</button>");
    let four = $("<button>4</button>");
    let five = $("<button>5</button>");
    let six = $("<button>6</button>");
    let seven = $("<button>7</button>");
    let eight = $("<button>8</button>");
    let nine = $("<button>9</button>");
    let zero = $("<button>0</button>");
    let clearbutton = $("<button>Clear</button>");
    let plusbutton = $("<button>+</button>");
    let minusbutton = $("<button>-</button>");
    let equalsbutton = $("<button>=</button>");

    clearbutton.attr("id", "clearbutton");
    plusbutton.attr("id", "plusbutton");
    minusbutton.attr("id", "minusbutton");

    $("body").append(
        valueinput,
        partial,              // <--- Add partial display to HTML
        one, two, three,
        four, five, six,
        seven, eight, nine,
        zero, clearbutton,
        plusbutton, minusbutton,
        equalsbutton
    );

    one.click(function () {
        valueinput.text(valueinput.text() + "1");
    });
    two.click(function () {
        valueinput.text(valueinput.text() + "2");
    });
    three.click(function () {
        valueinput.text(valueinput.text() + "3");
    });
    four.click(function () {
        valueinput.text(valueinput.text() + "4");
    });
    five.click(function () {
        valueinput.text(valueinput.text() + "5");
    });
    six.click(function () {
        valueinput.text(valueinput.text() + "6");
    });
    seven.click(function () {
        valueinput.text(valueinput.text() + "7");
    });
    eight.click(function () {
        valueinput.text(valueinput.text() + "8");
    });
    nine.click(function () {
        valueinput.text(valueinput.text() + "9");
    });
    zero.click(function () {
        valueinput.text(valueinput.text() + "0");
    });
    plusbutton.click(function () {
        $("#partial").html("");                       // Clear previous partial
        savedValue = Number(valueinput.text());       // Save input value
        savedOperation = "+";                         // Save operation
        valueinput.text("");                          // Clear input for next number
    });
    minusbutton.click(function () {
        $("#partial").html("");                       // Clear previous partial
        savedValue = Number(valueinput.text());       // Save input value
        savedOperation = "-";                         // Save operation
        valueinput.text("");                          // Clear input
    });
    clearbutton.click(function () {
        valueinput.text("");
        $("#partial").html("");
        savedValue = null;
        savedOperation = null;
    });

    equalsbutton.click(function () {
        let currentValue = Number(valueinput.text());
        let result;

        if (savedOperation === "+") {
            result = savedValue + currentValue;
        } else if (savedOperation === "-") {
            result = savedValue - currentValue;
        }

        valueinput.text(result);
        savedValue = null;
        savedOperation = null;
    });
}
