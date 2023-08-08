import { useState } from "react"
import ExamBodyFrame from "./ExamBodyFrame";
import ExamPlayerFrame from "./ExamPlayerFrame";

export type ExternalId = string;
export type ClientToken = string;

type ExamPointAuto = -1
type ExamPointManual = 0
export type ExamPointRange = 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12
export type ExamPoint = ExamPointAuto | ExamPointManual | ExamPointRange

export interface ExamFrameMessage {
  event: 'point:selected';
  data: any;
}

interface ExamProps {
  mediaUrl: string;
  clientToken: ClientToken;
  externalId: ExternalId;
  initialPoint?: ExamPoint;
}

const Exam = ({ mediaUrl, clientToken, externalId, initialPoint = -1, ...otherProps }: ExamProps) => {
  const language = 'en';
  const bodyLegend = false;
  let [ currentPoint, setCurrentPoint ] = useState<ExamPoint>(initialPoint)

  const bodyFrame = (
    <ExamBodyFrame
      mediaUrl={mediaUrl}
      clientToken={clientToken}
      externalId={externalId}
      language={language}
      point={initialPoint}
      onPointSelected={setCurrentPoint}
      frameOptions={{
        legend: false,
        pointsMode: 'extended',
      }}
    />
  )

  if (currentPoint < 1 && initialPoint > -1) {
    return (
      <div {...otherProps}>
        {bodyFrame}
        <section>
          <div>Please select point on the patient body</div>
        </section>
      </div>
    )
  }

  return (
    <div {...otherProps}>
      {bodyFrame}
      <ExamPlayerFrame
        mediaUrl={mediaUrl}
        clientToken={clientToken}
        externalId={externalId}
        language={language}
        point={currentPoint}
        frameOptions={{
          details: 2,
        }}
      />
    </div>
  )
}

export default Exam;
