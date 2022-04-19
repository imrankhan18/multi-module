console.log("hyy");
var options = document.getElementById("options");
var addfields = document.getElementById("addfields");
var removefields = document.getElementById("removefields");

addfields.onclick = function () {
  var div = document.createElement("div");
  div.setAttribute("id", "options");
  var newField = document.createElement("input");
  newField.setAttribute("type", "text");
  newField.setAttribute("name", "fields[]");
  newField.setAttribute("class", "fields");
  newField.setAttribute("placeholder", "field");
  div.appendChild(newField);
  var newField2 = document.createElement("input");
  newField2.setAttribute("type", "text");
  newField2.setAttribute("name", "value[]");
  newField2.setAttribute("class", "value");
  newField2.setAttribute("placeholder", "value");
  div.appendChild(newField2);
  options.appendChild(div);
};
removefields.onclick = function () {
  var input_tags = options.getElementsByTagName("div");
  if (input_tags.length >= 1) {
    options.removeChild(input_tags[input_tags.length - 1]);
  }
};
console.log("hyy");
var variations = document.getElementById("variations");
var addvariations = document.getElementById("addvariations");
var removevariations = document.getElementById("removevariations");
console.log("hyy");
addvariations.onclick = function () {
  console.log("hyy");
  var div = document.createElement("div");
  div.setAttribute("id", "variations");
  var newField = document.createElement("input");
  newField.setAttribute("type", "text");
  newField.setAttribute("name", "field1[]");
  newField.setAttribute("class", "field1");
  newField.setAttribute("placeholder", "value");
  div.appendChild(newField);
  var newField2 = document.createElement("input");
  newField2.setAttribute("type", "text");
  newField2.setAttribute("name", "field2[]");
  newField2.setAttribute("class", "field2");
  newField2.setAttribute("placeholder", "value");
  div.appendChild(newField2);
  var newField3 = document.createElement("input");
  newField3.setAttribute("type", "text");
  newField3.setAttribute("name", "field3[]");
  newField3.setAttribute("class", "field3");
  newField3.setAttribute("placeholder", "value");
  div.appendChild(newField3);
  variations.appendChild(div);
};
removevariations.onclick = function () {
  var input_tags = variations.getElementsByTagName("div");
  if (input_tags.length >= 1) {
    variations.removeChild(input_tags[input_tags.length - 1]);
  }
};