import { User } from "@/types/model/user"

export interface Discussion {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  title: string
  content: unknown
  slug: string
  user_id: number
  thumbnail: string | null
  original_filename: string | null
  path: string | null
  category_discussion_id: number
  // relations
  category: CategoryDiscussion
  user: User
}

export interface CategoryDiscussion {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  name: string
  slug: string
  // relations
  discussions: Discussion[]
}
