function findElementById(jsonArray, id) {
  // Loop through the array
  for (let i = 0; i < jsonArray.length; i++) {
    const element = jsonArray[i];

    // Check if the current element has the specified ID
    if (element.id === id) {
      return element; // Return the element if found
    }

    // If the current element has children, recursively search within them
    if (element.children && element.children.length > 0) {
      const childResult = findElementById(element.children, id);
      if (childResult) {
        return childResult; // Return the child element if found
      }
    }
  }

  // Return null if the element with the specified ID is not found
  return null;
}

export default findElementById;
