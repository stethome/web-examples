import Examination from '../components/media-api/examination';
import { mediaApiUrl, vendorToken } from '../components/media-api/config';

async function getClientToken () {
  let resp = await fetch(`${mediaApiUrl}/v2/security/token`, {
    headers: {Authorization: `Bearer ${vendorToken}`},
    cache: 'no-store',
  })

  if (!resp.ok) throw new Error('Failed to get client token')

  let json = await resp.json()

  return json.token
}

export default async function ExaminationPage() {
  // get client token in the Server Side component
  // it is IMPORTANT that the vendor token is NEVER exposed outside of your backend
  const clientToken = await getClientToken();

  return (
    <main className="flex min-h-screen flex-col items-center">
      <Examination
        className="flex flex-col items-center justify-between"
        clientToken={clientToken}
      />
    </main>
  )
}
