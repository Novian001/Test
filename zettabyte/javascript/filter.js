/**
 * Direction
 * Divide students to all of groups & students must sorted by first name
 *
 * Expected Result
 * [
 *   [
 *     { "firstName": "Belle", "lastName": "Norton" },
 *     { "firstName": "Finnley", "lastName": "Rennie" }
 *   ],
 *   [
 *     { "firstName": "Kai", "lastName": "Lyons" },
 *     { "firstName": "Peyton", "lastName": "Gardner" }
 *   ],
 *   [{ "firstName": "Tatiana", "lastName": "Dickerson" }]
 * ]
 */
const students = [
  { firstName: "Kai", lastName: "Lyons" },
  { firstName: "Belle", lastName: "Norton" },
  { firstName: "Finnley", lastName: "Rennie" },
  { firstName: "Tatiana", lastName: "Dickerson" },
  { firstName: "Peyton", lastName: "Gardner" },
];
const groups = 3;

function result(students, groups) {
  // your code here
  let result = [];
  let sortedStudents = students.sort((a, b) => {
    if (a.firstName > b.firstName) {
      return 1;
    } else {
      return -1;
    }
  });
  let groupSize = Math.ceil(sortedStudents.length / groups);
  for (let i = 0; i < groups; i++) {
    result.push(sortedStudents.slice(i * groupSize, (i + 1) * groupSize));
  }
  return result;
}

console.log(result(students, groups));
