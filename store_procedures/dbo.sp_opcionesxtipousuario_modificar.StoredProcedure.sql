USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_opcionesxtipousuario_modificar]    Script Date: 1/22/2024 7:21:14 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO
-- =============================================
-- Autor: Haydelis Hernandez 
-- Creado el: 16-05-2019
-- Descripcion:   OActualizar opción para agregar menú
-- Ejemplo:exec sp_opcionesxtipousuario_agregar 1,'accesodoxcxperfil.php'
-- =============================================

CREATE PROCEDURE [dbo].[sp_opcionesxtipousuario_modificar]

	@ptipousuarioid   INT,              -- identificador del tipo de usuario o perfil
	@popcionid        NVARCHAR(50),      -- codigo opcion menu
    @estado			  INT
AS    
BEGIN
      SET NOCOUNT ON;
      
      IF EXISTS ( SELECT opcionid FROM opcionesxtipousuario WHERE tipousuarioid = @ptipousuarioid  ) 
		BEGIN 
			UPDATE opcionesxtipousuario SET 
				consulta = @estado,
				modifica = @estado,
				elimina = @estado,
				crea = @estado,
				ver = @estado
			WHERE 
				tipousuarioid = @ptipousuarioid AND opcionid = @popcionid		
		END                  
      RETURN
END
GO
