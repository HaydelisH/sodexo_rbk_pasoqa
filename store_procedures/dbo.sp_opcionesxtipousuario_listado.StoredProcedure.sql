USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionesxtipousuario_listado]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- Autor: Cristian Soto
-- Creado el: 29/08/2018
-- Descripcion:   listado de opciones según perfil
-- Ejemplo:exec sp_opcionesxtipousuario_listado 1
-- =============================================
CREATE  PROCEDURE [dbo].[sp_opcionesxtipousuario_listado]
@ptipousuarioid   INT              -- identificador del tipo de usuario o perfil

      
AS    
BEGIN
      SET NOCOUNT ON;
      
      SELECT 
      opcionid,
      consulta,
      modifica,
      elimina,
      crea,
      ver
      FROM opcionesxtipousuario
      WHERE tipousuarioid = @ptipousuarioid
                      
      RETURN
END
GO
