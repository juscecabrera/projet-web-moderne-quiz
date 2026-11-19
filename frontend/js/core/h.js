export function h(tag, props, ...children) {
  const element = document.createElement(tag);

  Object.entries(props ?? {}).forEach(([name, value]) => {
    if (value === undefined || value === null || value === false) {
      return;
    }

    if (name.startsWith('on')) {
      element.addEventListener(name.slice(2).toLowerCase(), value);
    } else if (name === 'className') {
      element.className = value;
    } else if (value === true) {
      element[name] = true;
    } else {
      element.setAttribute(name, value);
    }
  });

  children.flat().forEach((child) => {
    if (child === undefined || child === null || child === false) {
      return;
    }

    element.append(child instanceof Node ? child : document.createTextNode(String(child)));
  });

  return element;
}
