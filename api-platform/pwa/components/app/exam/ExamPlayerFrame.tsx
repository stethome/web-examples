import {ClientToken, ExamPoint, ExternalId} from "./Exam";
import {useEffect, useState} from "react";
import ExamFrameLoader from "./ExamFrameLoader";

interface PlayerFrameProps {
  mediaUrl: string;
  clientToken: ClientToken;
  externalId: ExternalId;
  point: ExamPoint;
  language: string;
  frameOptions: PlayerFrameOptions;
  onPointSelected: (point: number) => void;
}

interface PlayerFrameOptions {
  details?: PlayerDetailsMode;
}

enum PlayerDetailsMode {
  None = 0,
  Simple = 1,
  Extended = 2,
}

const ExamPlayerFrame = ({
 mediaUrl,
 clientToken,
 externalId,
 point,
 language,
 frameOptions,
}: PlayerFrameProps) => {
  const options: PlayerFrameOptions = {
    details: PlayerDetailsMode.Extended,
    ...frameOptions
  }
  const frameUrl = `${mediaUrl}v2/pulmonary/frame/${externalId}/${point}/${language}?details=${options.details}&bearer=${clientToken}`;

  let [ loaded, setLoaded ] = useState(false)
  let [ iframeSrc, setIframeSrc ] = useState('')

  useEffect(() => {
    setLoaded(false)
  }, [iframeSrc])

  function initializeIframeRef(iframe: HTMLIFrameElement) {
    if (iframe !== null) {
      // see: https://github.com/facebook/react/issues/18752
      iframe.addEventListener("load", () => setLoaded(true));
      setIframeSrc(frameUrl)
    }
  }

  if (point < 1) {
    return <></>
  }

  return (
    <section className="flex flex-col pt-10 h-full">
      <iframe
        className={ loaded ? 'h-full' : 'invisible'}
        scrolling="no"
        frameBorder="0"
        allow="screen-wake-lock"
        importance="high"
        src={iframeSrc}
        ref={initializeIframeRef}
      />
      <ExamFrameLoader loaded={loaded}>
        <span>Loading player frame...</span>
      </ExamFrameLoader>
    </section>
  )
}

export default ExamPlayerFrame
