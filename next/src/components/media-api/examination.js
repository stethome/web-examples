'use client';
import { useState } from "react";
import { ExaminationBodyFrame, ExaminationPlayerFrame } from "./frame";

export default function Examination({ clientToken, initialPoint = -1, ...otherProps }) {
  let [ currentPoint, setCurrentPoint ] = useState(initialPoint)
  let [ language, setLanguage ] = useState('en')
  let [ visitId, setVisitId ] = useState('e61bebe693ae350592e2fcf6dbfb8dc8')
  let [ playerDetails, setPlayerDetails ] = useState(2)
  let [ bodyLegend, setBodyLegend ] = useState(false)
  let [ bodyPointsMode, setBodyPointsMode ] = useState('extended')

  const sectionConfig = (
    <section>
      <div>
        <label>
          Visit id
          <input className="ml-4" type="text" name="visitId" value={visitId} onChange={(e) => setVisitId(e.target.value)} />
        </label>

        <label className="ml-4">
          Language
          <select className="ml-4" value={language} onChange={(e) => setLanguage(e.target.value)}>
            <option value="en">English</option>
            <option value="pl">Polish</option>
          </select>
        </label>
      </div>
      <div>
        <label>
          Player details
          <select className="ml-4" value={playerDetails} onChange={(e) => setPlayerDetails(e.target.value)}>
            <option value="0">None</option>
            <option value="1">Simple</option>
            <option value="2">Extended</option>
          </select>
        </label>
        <label className="ml-4">
          Legend
          <input type="checkbox" value={bodyLegend} onChange={(e) => setBodyLegend(e.target.checked )} />
        </label>
        <label className="ml-4">
          Points mode
          <select className="ml-4" value={bodyPointsMode} onChange={(e) => setBodyPointsMode(e.target.value)}>
            <option value="simple">Simple</option>
            <option value="extended">Extended</option>
          </select>
        </label>
      </div>
    </section>
  )

  const bodyFrame = (
    <ExaminationBodyFrame
      className="w-screen"
      clientToken={clientToken}
      visitId={visitId}
      language={language}
      point={initialPoint}
      legend={bodyLegend}
      pointsMode={bodyPointsMode}

      onPointSelected={setCurrentPoint}
    />
  )

  if (currentPoint < 1) {
    return (
      <div {...otherProps}>
        {sectionConfig}
        {bodyFrame}
        <section>
          <div>Please select point on the patient body</div>
        </section>
      </div>
    )
  }

  return (
    <div {...otherProps}>
      {sectionConfig}
      {bodyFrame}
      <ExaminationPlayerFrame
        clientToken={clientToken}
        visitId={visitId}
        language={language}
        point={currentPoint}
        details={playerDetails}
      />
    </div>
  )
}
