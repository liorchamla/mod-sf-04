import React, { useState } from "react";
import ReactDOM from "react-dom";

const VoteComponent = () => {
  const [displayButtons, setDisplayButtons] = useState(true);

  const handleClick = () => {
    setDisplayButtons(false);
  };

  return (
    <>
      {displayButtons ? (
        <>
          <button onClick={() => handleClick()}>Bullit</button>
          <button onClick={() => handleClick()}>Ripper</button>
        </>
      ) : (
        <h2>Vous avez déjà voté</h2>
      )}
    </>
  );
};

ReactDOM.render(<VoteComponent />, document.querySelector("#votes"));
