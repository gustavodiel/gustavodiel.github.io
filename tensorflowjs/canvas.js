// Gustavo Diel
//

let xValues = [];
let yValues = [];

// y = mx * b
let m, b;

function setup() {
    createCanvas(400, 400);

    m = tf.variable(tf.scalar(random(1)));
    b = tf.variable(tf.scalar(random(1)));
}

function mousePressed() {
    let x = map(mouseX, 0, width, 0, 1);
    let y = map(mouseY, 0, height, 1, 0);

    xValues.push(x);
    yValues.push(y);

}

function draw() {
    background(0);

    stroke(255);
    strokeWeight(16);

    for (let i = 0; i < xValues.length; ++i) {
        let x = map(xValues[i], 0, 1, 0, width);
        let y = map(yValues[i], 0, 1, height, 0);

        point(x, y);

    }

}