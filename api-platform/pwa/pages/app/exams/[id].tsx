import Head from "next/head";
import {useRouter} from "next/router";
import {GetServerSideProps, InferGetServerSidePropsType} from "next";
import {withApiToken} from "../../../utils/auth";
import {fetch} from "../../../utils/dataAccess";
import Exam from "../../../components/app/exam/Exam";
import { ExamMediaApiModel } from "../../../types/ExamMediaApiModel";

export const getServerSideProps: GetServerSideProps<ExamMediaApiModel> = withApiToken(async ({ apiToken, context, }) => {
  const externalId = context.params!.id as string;
  const response = await fetch<ExamMediaApiModel>(`/api/exam_models/${externalId}/token`, {
    headers: {
      "Authorization": `Bearer ${apiToken}`
    }
  });

  if (!response) {
    throw 'Could not fetch media api token'
  }

  return { props: response.data }
})

const ExamView = ({ token: clientToken, mediaUrl, externalId }: InferGetServerSidePropsType<typeof getServerSideProps>) => {
  const router = useRouter();
  const uuid = router.query.id as string;

  return (
    <>
      <Head>
        <title>Examination - {uuid}</title>
      </Head>
      <Exam mediaUrl={mediaUrl} clientToken={clientToken} externalId={externalId} className="h-full" />
    </>
  )
}

export default ExamView;
