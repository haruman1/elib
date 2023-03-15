function handleCopyClick(evt) {
  // get the children of the parent element
  const { children } = evt.target.parentElement;
  // grab the first element (we append the copy button on afterwards, so the first will be the code element)
  // destructure the innerText from the code block
  const { innerText } = Array.from(children)[0];
  // copy all of the code to the clipboard
  copyToClipboard(innerText);
  // alert to show it worked, but you can put any kind of tooltip/popup to notify it worked
  alert(innerText);
}
