import { User } from "@/types/model/user"

export interface Discussion {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  title: string
  content: string
  slug: string
  user_id: number
  thumbnail?: string | null
  original_filename?: string | null
  path?: string | null
  category_discussion_id: number
  // relations
  category?: CategoryDiscussion
  user?: User
  comments?: CommentDiscussion[]
}

export interface CommentDiscussion {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  deleted_at?: string | null
  discussion_id: number
  user_id: number
  parent_id?: number | null
  content: string
  answer_to?: number | null
  // relations
  replies?: CommentDiscussion[]
  user?: User
  discussion?: Discussion
  answer_to_user?: User
}

export interface CategoryDiscussion {
  // columns
  id: number
  created_at?: string | null
  updated_at?: string | null
  name: string
  slug: string
  // relations
  discussions?: Discussion[]
}
