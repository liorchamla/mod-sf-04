import React, { useEffect, useState } from "react";
import ReactDOM from "react-dom";

const VoteComponent = (props) => {
  const [displayButtons, setDisplayButtons] = useState(false);
  const [reponses, setReponses] = useState([]);

  useEffect(() => {
    fetch("/api/survey/" + props.surveyId)
      .then((response) => response.json())
      .then((json) => {
        setDisplayButtons(json.canVote);
        setReponses(json.reponses);
      });
  }, []);

  const handleClick = (reponseId) => {
    const url = "/" + props.surveyId + "/vote/" + reponseId;

    fetch(url, {
      method: "POST",
    }).then((reponse) => {
      setDisplayButtons(false);
    });
  };

  return (
    <>
      {displayButtons ? (
        <>
          {reponses.map((r) => (
            <button onClick={() => handleClick(r.id)}>{r.text}</button>
          ))}
        </>
      ) : (
        <h2>Vous avez déjà voté</h2>
      )}
    </>
  );
};

const container = document.querySelector("#votes");

// const surveyId = container.getAttribute('data-survey-id');
const surveyId = container.dataset.surveyId;

ReactDOM.render(<VoteComponent surveyId={surveyId} />, container);
