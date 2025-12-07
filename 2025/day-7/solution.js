import fs from 'fs';
const filePath = 'input-test.txt';


fs.readFile(filePath, {}, function(err,data){
  const  map = data.toString().split('\n').map(line => line.split(''));

  const beams = [];
  // const paths = [];
  let result1 = 0;
  let buffer = [];

  map.forEach((line, row) => {
    // console.log('Row '+row);
    line.forEach((character, index) => {
      switch (character) {
        case 'S':
          beams.push(index);
          buffer[index] = 1;
          break;
        case '^':
          if (beams.indexOf(index) !== -1) {
            // Part 1
            result1++;
            if (!beams.includes(index-1)) beams.push(index-1);
            if (!beams.includes(index+1)) beams.push(index+1);
            beams.splice(beams.indexOf(index), 1);

            // Part 2
            if (buffer[index+1] === undefined) buffer[index+1] = 0;
            if (buffer[index-1] === undefined) buffer[index-1] = 0;
            buffer[index+1] += buffer[index];
            buffer[index-1] += buffer[index];
            buffer[index] = 0;
          }
          break;
        default:
      }
    });
  });

  // First try with recursive: too long, failed
  // result2 = countTachyonBeams(map[0].indexOf('S'), 2, map);

  // Better solution
  const result2 = buffer.reduce((a, b) => a + b, 0);

  console.log(`Result1: ${result1}`);
  console.log(`Result2: ${result2}`);
});



// Part2 fail: Recursive function: does not work for long input...
function countTachyonBeams(index, row, map)
{
  if (row + 2 >= map.length) {
    return 1;
  }

  if (map[row][index] === '^') {
    const countLeft = countTachyonBeams(index - 1, row + 2, map);
    const countRight = countTachyonBeams(index + 1, row + 2, map);

    return countLeft + countRight;
  } else {
    return countTachyonBeams(index, row + 2, map);
  }
}
