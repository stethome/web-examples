/**
 * @typedef {Object} DotNetObjectReference
 * @function invokeMethodAsync
 */

/** 
 * @param {HTMLIFrameElement} iframe
 * @param {DotNetObjectReference} component
 */
export function SetupFrame(iframe, component) {
    console.debug('SetupFrame', iframe, component)
    
    window.addEventListener('message', (frameMessage) => {
        // filter out all messages not from our iframe
        if (iframe?.contentWindow !== frameMessage.source) return

        const { event, data } = JSON.parse(frameMessage.data);

        // event was triggered by scripts, prevent recursion
        if (data.external) return

        switch (event) {
            case "point:selected": {
                // call Blazor Component method
                component.invokeMethodAsync('OnPointSelectedJS', data.point)
                break;
            }
            default: {
                console.log('Unknown event', event, data)
            }
        }
    })
}