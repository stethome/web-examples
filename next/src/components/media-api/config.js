import 'server-only' // prevents us from accidentally using server-only modules in client components
import * as clientConfig from './config-client'

export const mediaApiUrl = clientConfig.mediaApiUrl;
export const vendorToken = '<put your vendor-token here>';
