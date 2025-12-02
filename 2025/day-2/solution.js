import fs from 'fs';
const filePath = 'input.txt';


// Part one
fs.readFile(filePath, {}, function(err,data){
    let result = 0;
    const ranges = data.toString().split(',');
    ranges.forEach(range => {
      // console.log('Processing range: ' + range);
      const ids = range.toString().split('-');
      // console.log('IDs: ' + ids);
      const firstId = parseInt(ids[0]);
      const lastId = parseInt(ids[1]);
      // console.log('First ID: ' + firstId + ', Last ID: ' + lastId);

      for (let i = firstId, len = lastId; i <= len; i++) {
        // console.log('Checking ID: ' + i);
        // console.log('ID Length: ' + i.toString().length);

        if (i.toString().length % 2 === 0) {
          const part1 = i.toString().substring(0, i.toString().length / 2);
          const part2 = i.toString().substring(i.toString().length / 2);

          if (part1 === part2) {
            // console.log(`Invalid ID found: ${i}`);
            result += i;
          }
        }
      }
    });
    console.log('Part 1 result: ' + result);
});

// Part two
fs.readFile(filePath, {}, function(err,data){
  let result = 0;
  const ranges = data.toString().split(',');
  ranges.forEach(range => {
    // console.log('Processing range: ' + range);
    const ids = range.toString().split('-');
    // console.log('IDs: ' + ids);

    const firstId = parseInt(ids[0]);
    const lastId = parseInt(ids[1]);

    for (let i = firstId, len = lastId; i <= len; i++) {
      // console.log('Checking ID: ' + i);

      idLoop: for (let s = 1; s <= (i.toString().length / 2) ; s++) {
        const part1 = i.toString().substring(0, s);
        const restOfString = i.toString().substring(s);

        let test = part1;

        do {
          if (test === restOfString) {
            // console.log(`Invalid ID found: ${i}`);
            result += i;
            break idLoop;
          }
          test = part1 + '' + test;
        } while(test.length <= restOfString.length);
      }
    }
  });
  console.log('Part 2 result: ' + result);
});
