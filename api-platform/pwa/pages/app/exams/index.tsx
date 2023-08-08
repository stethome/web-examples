import {GetServerSideProps, InferGetServerSidePropsType, NextComponentType, NextPageContext} from "next";
import Head from "next/head";
import ExamList from "../../../components/app/exam/ExamList";
import {fetch} from "../../../utils/dataAccess";
import {ExamModel} from "../../../types/ExamModel";
import {withApiToken} from "../../../utils/auth";
import {PagedCollection} from "../../../types/collection";

export const getServerSideProps: GetServerSideProps<{ exams: ExamModel[] }> = withApiToken(async ({ apiToken, context }) => {
  const response = await fetch<PagedCollection<ExamModel>>('/api/exam_models', {
    headers: {
      "Authorization": `Bearer ${apiToken}`
    }
  });

  if (!response) {
    throw 'Could not fetch exams'
  }

  return { props: { exams: response.data["hydra:member"] }}
})

const ExamsPage = ({ exams }: InferGetServerSidePropsType<typeof getServerSideProps>) => {
  return (
    <div>
      <div>
        <Head>
          <title>StethoMe Demo Platform</title>
        </Head>
      </div>
      <main>
        <ExamList exams={exams} />
      </main>
    </div>
  )
}

export default ExamsPage
