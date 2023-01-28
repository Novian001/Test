/**
 * Direction:
 * Swap these numbers without using temporary variable
 *
 * Expected Result:
 * {
 *  a: 4,
 *  b: 2
 * }
 */
let numbers = {
  a: 2,
  b: 4,
};

function result(numbers) {
  // Your Code Here
  let a = numbers.a;
  let b = numbers.b;
  numbers.a = b;
  numbers.b = a;
  return numbers;
}

console.log(result(numbers));
