USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_tiposusuarios_todos]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Cristian Soto
-- Creado el: 3/09/2016
-- Descripcion:   Consulta tipos de usuario por holding
-- Ejemplo:exec sp_tiposusuarios_todos 
-- =============================================
CREATE PROCEDURE [dbo].[sp_tiposusuarios_todos]
@ptipousuarioid   INT               -- id tipo de usuario
      
AS    
BEGIN
      SET NOCOUNT ON;

      IF (@ptipousuarioid = 1)
      BEGIN
      SELECT 
            tipousuarioid,
            nombre
            FROM tiposusuarios
      END
      
      IF (@ptipousuarioid <> 1)
      BEGIN
      SELECT 
            tipousuarioid,
            nombre
            FROM tiposusuarios
            WHERE tipousuarioid <> 1
      END
      
      RETURN;
END
GO
