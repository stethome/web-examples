'use client';
import { useEffect, useState } from "react";
import { mediaApiUrl } from "./config-client";

function FrameLoader({ loaded, children }) {
  return (
    <div className={ loaded ? 'hidden' : 'absolute w-screen h-72 text-center'} style={{ top: 0, left: 0}}>
      {children}
    </div>
  )
}

export function ExaminationBodyFrame({
  clientToken,
  visitId,
  point = -1,
  legend = false,
  language = 'en',
  pointsMode = 'extended',
  onPointSelected = () => {},
}) {
  const frameUrl = `${mediaApiUrl}/v2/pulmonary/frame-body/${visitId}/${point}/${language}?legend=${legend}&pointsMode=${pointsMode}&bearer=${clientToken}`;

  let [ loaded, setLoaded ] = useState(false)
  let [ iframe, setIframeRef ] = useState(null)
  let [ iframeSrc, setIframeSrc ] = useState('')

  useEffect(() => {
    setLoaded(false)
  }, [iframeSrc])

  /** @param {HTMLIFrameElement} iframe */
  function initializeIframeRef(iframe) {
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

  /** @param {MessageEvent<string>} frameMessage */
  function onMessage(frameMessage) {
    // filter out all messages not from our iframe
    if (iframe?.contentWindow !== frameMessage.source) return

    const { event, data } = JSON.parse(frameMessage.data);

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
    <section className="relative w-screen flex flex-col">
      <iframe
        className={ loaded ? 'w-screen h-72' : 'invisible'}
        scrolling="no"
        frameBorder="0"
        allowtransparency="true"
        importance="high"
        src={iframeSrc}
        ref={initializeIframeRef}
      />
      <FrameLoader loaded={loaded}>
        <span>Loading body frame...</span>
      </FrameLoader>
    </section>
  )
}

export function ExaminationPlayerFrame({
  clientToken,
  visitId,
  point,
  details = 2,
  language = 'en',
}) {
  const frameUrl = `${mediaApiUrl}/v2/pulmonary/frame/${visitId}/${point}/${language}?details=${details}&bearer=${clientToken}`;

  let [ loaded, setLoaded ] = useState(false)
  let [ iframeSrc, setIframeSrc ] = useState('')

  useEffect(() => {
    setLoaded(false)
  }, [iframeSrc])

  /** @param {HTMLIFrameElement} iframe */
  function initializeIframeRef(iframe) {
    if (iframe !== null) {
      // see: https://github.com/facebook/react/issues/18752
      iframe.addEventListener("load", () => setLoaded(true));
      setIframeSrc(frameUrl)
    }
  }

  return (
    <section className="relative w-screen flex flex-col pt-10">
      <iframe
        className={ loaded ? 'w-screen h-screen' : 'invisible'}
        scrolling="no"
        frameBorder="0"
        allowtransparency="true"
        importance="high"
        src={iframeSrc}
        ref={initializeIframeRef}
      />
      <FrameLoader loaded={loaded}>
        <span>Loading player frame...</span>
      </FrameLoader>
    </section>
  )
}
