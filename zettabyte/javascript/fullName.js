/**
 * Direction:
 * Find all fields that have different value & must can detect all field dynamically
 *
 * Expected Result:
 * ['firstName', 'lastName']
 *
 */
const data = [
  { firstName: "Adi", lastName: "Nugroho", age: 25 },
  { firstName: "Deddy", lastName: "Dores", age: 25 },
];

function result(data) {
  // your code here
  let result = [];
  let firstData = data[0];
  let keys = Object.keys(firstData);
  for (let i = 0; i < keys.length; i++) {
    let key = keys[i];
    let isDifferent = false;
    for (let j = 1; j < data.length; j++) {
      if (firstData[key] !== data[j][key]) {
        isDifferent = true;
        break;
      }
    }
    if (isDifferent) {
      result.push(key);
    }
  }
  return result;
}

console.log(result(data));
