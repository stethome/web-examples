import { useEffect, useState } from "react";
import { ClientToken, ExamFrameMessage, ExamPoint, ExternalId } from "./Exam";
import ExamFrameLoader from "./ExamFrameLoader";

interface BodyFrameProps {
  mediaUrl: string;
  clientToken: ClientToken;
  externalId: ExternalId;
  point: ExamPoint;
  language: string;
  frameOptions: BodyFrameOptions;
  onPointSelected: (point: number) => void;
}

interface BodyFrameOptions {
  pointsMode?: 'extended' | 'simple';
  legend?: boolean;
}

const ExamBodyFrame = ({
  mediaUrl,
  clientToken,
  externalId,
  point,
  language,
  onPointSelected = () => {},
  frameOptions,
  ...otherProps
}: BodyFrameProps) => {
  let options: BodyFrameOptions = {
    pointsMode: 'extended',
    legend: false,
    ...frameOptions
  }

  const frameUrl = `${mediaUrl}v2/pulmonary/frame-body/${externalId}/${point}/${language}?legend=${options.legend}&pointsMode=${options.pointsMode}&bearer=${clientToken}`;

  let [ loaded, setLoaded ] = useState(false)
  let [ iframe, setIframeRef ] = useState<HTMLIFrameElement | null>(null)
  let [ iframeSrc, setIframeSrc ] = useState('')

  useEffect(() => {
    setLoaded(false)
  }, [iframeSrc])

  function initializeIframeRef(iframe: HTMLIFrameElement) {
    if (iframe !== null) {
      setIframeRef(iframe)

      // see: https://github.com/facebook/react/issues/18752
      iframe.addEventListener("load", () => setLoaded(true))
      setIframeSrc(frameUrl)

      window.addEventListener('message', onMessage)
    } else {
      window.removeEventListener('message', onMessage)
    }
  }

  function onMessage(frameMessage: MessageEvent<string>) {
    // filter out all messages not from our iframe
    if (iframe?.contentWindow !== frameMessage.source) return

    const { event, data } = JSON.parse(frameMessage.data) as ExamFrameMessage;

    // event was triggered by scripts, prevent recursion
    if (data.external) return

    switch (event) {
      case "point:selected": {
        onPointSelected(data.point)
        break;
      }
      default: {
        console.log('Unknown event', event, data)
      }
    }
  }

  return (
    <section className="flex flex-col">
      <iframe
        className={ loaded ? 'h-72' : 'hidden'}
        scrolling="no"
        frameBorder="0"
        importance="high"
        src={iframeSrc}
        ref={initializeIframeRef}
      />
      <ExamFrameLoader loaded={loaded}>
        <span>Loading body frame...</span>
      </ExamFrameLoader>
    </section>
  )
}

export default ExamBodyFrame
