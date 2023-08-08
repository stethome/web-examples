
export default function ExamFrameLoader({ loaded, children }: { loaded: boolean, children: React.ReactNode }) {
  return (
    <div className={ loaded ? 'hidden' : 'h-72 text-center'}>
      {children}
    </div>
  )
}
