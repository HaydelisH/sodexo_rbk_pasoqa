USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_usuariosmant_obtener_20210927]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS OFF
GO
SET QUOTED_IDENTIFIER OFF
GO

-- =============================================
-- Autor: Cristian Soto
-- Creado el: 5/09/2016
-- Descripcion:  Obtener usuario 
-- Ejemplo:exec sp_usuariosmant_obtener '11111111-1'
-- =============================================
CREATE PROCEDURE [dbo].[sp_usuariosmant_obtener_20210927]
	@pusuarioid                     NVARCHAR(50)                -- identificador del tipo de usuario
                
AS          
BEGIN
    SET NOCOUNT ON;
                
    SELECT 
		usuarios.usuarioid AS newusuarioid,
		personas.nombre,
		personas.appaterno,
		personas.apmaterno,
		personas.correo,
		usuarios.nombreusuario,
		usuarios.loginExterno as idloginExterno,
		usuarios.tipousuarioid,
		usuarios.idFirma,
		--e.rolid,
		usuarios.rolid,
		usuarios.RutEmpresa,
		usuarios.centrocostoid,
		f.Descripcion
    FROM usuarios 
		LEFT JOIN personas ON usuarios.usuarioid = personas.personaid
		LEFT JOIN Empleados e ON usuarios.usuarioid = e.empleadoid
		LEFT JOIN Firmas f ON usuarios.idFirma = f.idFirma
    WHERE usuarios.usuarioid = @pusuarioid
                
    RETURN
END
GO
